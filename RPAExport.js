import * as dotenv from 'dotenv'
dotenv.config()
import puppeteer from 'puppeteer-extra';
import RecaptchaPlugin from "puppeteer-extra-plugin-recaptcha"
import { executablePath } from "puppeteer"
import winston from "winston"

// create a logger that logs to a file
const logger = winston.createLogger({
  level: 'error',
  transports: [
    new winston.transports.File({ filename: 'error.log' })
  ]
});

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
  browser = await puppeteer.launch({
    executablePath: '/usr/bin/chromium-browser',
    args: ['--no-sandbox']
  })
  const page = await browser.newPage()
  let number_of_imports = 0
  page.on('console', messege => {
    let text = messege.text()
    if (text.includes('tstore_check_import_status')) {
      number_of_imports += 1
    }
    console.log('Browser console text: ', text)
  })
  await page.goto(AdminUrl)

  // wrap each action in a try-catch block that logs errors to the logger
  try {
    await page.waitForSelector("#form");
  } catch (error) {
    logger.error("Error waiting for selector: " + error);
    process.exit(1);
  }

  console.log("page loaded");

  try {
    await page.solveRecaptchas();
  } catch (error) {
    logger.error("Error solving captcha: " + error);
    process.exit(1);
  }

  console.log("captcha solved");

  try {
    await page.screenshot({ path: "shot1.png" });
  } catch (error) {
    logger.error("Error taking screenshot: " + error);
    process.exit(1);
  }

  try {
    await page.click("input[name=email]");
    await page.type("input[name=email]", Login);
    console.log("email entered")
  } catch (error) {
    logger.error("Error entering login: " + error);
    process.exit(1);
  }

  try {
    await page.click("input[name=password]");
    await page.type("input[name=password]", Password);
    console.log("password entered")
  } catch (error) {
    logger.error("Error entering password: " + error);
    process.exit(1);
  }

  try {
    await page.click("#send");
    await page.screenshot({ path: "shot2.png" });
    console.log("login form sending")
  } catch (error) {
    logger.error("Error logging in: " + error);
    process.exit(1);
  }

  // try {
  //   await page.goto('https://store.tilda.cc/store/?projectid=4201264');
  // } catch (error) {
  //   logger.error("Go to kit site page in: " + error);
  //   process.exit(1);
  // }

  try {
    await page.waitForNavigation();

    // await page.evaluate(() => location.href = "https://store.tilda.cc/store/?projectid=4201264")
    // await page.goto("https://store.tilda.cc/store/?projectid=4201264")

    await page.click("[href='/projects/?projectid=4201264']")
    await page.waitForSelector("[href='/identity/gostore/?projectid=4201264']")
  } catch (error) {
    await page.screenshot({ path: "shot3.png" });
    logger.error("Error navigating to products: " + error);
    process.exit(1);
  }

  try {
    await page.click("[href='/identity/gostore/?projectid=4201264']")
    await page.waitForNavigation();
    console.log("in products")
    // await page.click(".tstore__etc-btn__menu-item:first-child")
    await page.evaluate(() => tstore_start_import('csv'))
  } catch (error) {
    logger.error("Error starting import: " + error);
    process.exit(1);
  }

  try {
    await page.screenshot({ path: "shot2.png" });
    const [fileChooser] = await Promise.all([
      page.waitForFileChooser(),
      page.click('.js-import-load-file-btn')
    ])
    console.log("file loaded")

    const uploader = await page.$("input[type='file']")
    await uploader.uploadFile(process.cwd() + "/to_import.csv")
    await page.click('.js-import-load-data')
    await page.screenshot({ path: "shot2.png" })
    await delay(60 * 1000)
  } catch (error) {
    logger.error("Error uploading file: " + error);
    process.exit(1);
  }

  try {
    await page.click('[name="importphoto"]')
    await page.click(".btn_importcsv_proccess")
    console.log("finish")
    await delay(20 * 60 * 1000)
    await page.screenshot({ path: "shot3.png" })
  } catch (error) {
  logger.error("Error processing import: " + error);
   process.exit(1);
  }
  
  browser.close()
})()

function delay(timeout) {
  return new Promise((resolve) => {
    setTimeout(resolve, timeout)
  })
}
