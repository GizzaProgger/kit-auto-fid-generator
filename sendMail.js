
import nodemailer from "nodemailer"

import * as dotenv from 'dotenv'
dotenv.config()

const sendMail = (to, subject, text) => {
    // Создаем транспорт для отправки письма
    console.log(process.env.SMTP_LOGIN, process.env.SMTP_PASSWORD)
    let transporter = nodemailer.createTransport({
        host: 'smtp.gmail.com',
        port: 587,
        ssl: true,
        auth: {
            user: process.env.SMTP_LOGIN,
            pass: process.env.SMTP_PASSWORD,
        },
    });

    // Определяем настройки письма
    let mailOptions = {
        from: process.env.SMTP_LOGIN,
        to: to,
        subject: subject,
        text: text
    };

    // Отправляем письмо
    transporter.sendMail(mailOptions, (error, info) => {
        if (error) {
            console.error(error);
        } else {
            console.log('Письмо отправлено: ' + info.response);
        }
    });
};

sendMail("147rawil147gmail.com", 'test', 'hello world')