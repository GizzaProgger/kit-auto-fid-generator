import express from "express"
import { exec } from "child_process";


const app = express()
const port = 3000

app.get('/', async (req, res) => {
  res.send('Импорт запустился!')
  exec("./start.sh", (error, stdout, stderr) => {
    if (error) {
      console.log(`error: ${error.message}`);
      return;
    }
    if (stderr) {
      console.log(`stderr: ${stderr}`);
      return;
    }
    console.log(`stdout: ${stdout}`);
  })
})

app.listen(port, () => {
  console.log(`Example app listening on port ${port}`)
})