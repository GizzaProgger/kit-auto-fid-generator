import * as dotenv from 'dotenv'
dotenv.config()
import puppeteer from 'puppeteer-extra';
import RecaptchaPlugin from "puppeteer-extra-plugin-recaptcha"
import { executablePath } from "puppeteer"

(async () => {

  const AdminUrl = process.env.ADMIN_URL
  const Login = process.env.ADMIN_LOGIN
  const Password = process.env.ADMIN_PASSWORD
  const TargetUrl = process.env.TARGET_URL
  const SolverToken = process.env.RECAPTCHA_SOLVER_TOKEN

  puppeteer.use(
    RecaptchaPlugin({
      provider: {
        id: '2captcha',
        token: SolverToken // REPLACE THIS WITH YOUR OWN 2CAPTCHA API KEY ⚡
      },
      visualFeedback: true // colorize reCAPTCHAs (violet = detected, green = solved)
    })
  )

  let browser
  try {
    browser = await puppeteer.launch({
      headless: false,
      executablePath: executablePath(),
      args: ['--no-sandbox']
    })
  } catch (error) {
    browser = await puppeteer.launch({
      headless: true,
      executablePath: '/usr/bin/chromium-browser',
      args: ['--no-sandbox']
    })
  }
  const page = await browser.newPage()
  page.on('console', messege => console.log(messege))
  await page.goto(AdminUrl)

  await page.waitForSelector("#form");
  console.log("page loaded")
  await page.solveRecaptchas()
  console.log("captcha solved")
  await page.click("input[name=email]");
  await page.type("input[name=email]", Login);

  await page.click("input[name=password]");
  await page.type("input[name=password]", Password);
  
  await page.click("#send");
  await page.waitForNavigation();
  console.log("loginned")
  // await page.evaluate(() => location.href = "https://store.tilda.cc/store/?projectid=4201264")
  // await page.goto("https://store.tilda.cc/store/?projectid=4201264")


  await page.click("[href='/projects/?projectid=4201264']")
  await page
    .waitForSelector("[href='/identity/gostore/?projectid=4201264']")

  await page.click("[href='/identity/gostore/?projectid=4201264']")
  await page.waitForNavigation();
  console.log("in products")
  // await page.click(".tstore__etc-btn__menu-item:first-child")
  await page.evaluate(() => tstore_start_import('csv'))
  
  const [fileChooser] = await Promise.all([
    page.waitForFileChooser(),
    page.click('.js-import-load-file-btn')
  ])
  console.log("file loaded")

  const uploader = await page.$("input[type='file']")
  await uploader.uploadFile("./to_import.csv")
  await page.click('.js-import-load-data')
  await page.screenshot({ path: "shot.png" })
  await page.waitForSelector('[name="importphoto"]')
  

  await page.click('[name="importphoto"]')
  await page.click(".btn_importcsv_proccess")
  console.log("finish")
  browser.close()
})()

function delay(timeout) {
  return new Promise((resolve) => {
    setTimeout(resolve, timeout)
  })
}