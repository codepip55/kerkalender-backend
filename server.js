import express from 'express';
import cors from 'cors';
import cookieParser from 'cookie-parser';

const app = express();
app.use(cors({ origin: 'http://localhost:4200', credentials: true }));
const cookieSecret = process.env.COOKIE_SECRET
app.use(cookieParser(process.env.COOKIE_SECRET) || 'secret');
app.use(express.json());

app.post('/set-refresh-cookie', (req, res) => {
    // Extract cookie from body
    const { refresh_token } = req.body;

    res.cookie(process.env.AUTH_COOKIE_NAME, refresh_token, {
        httpOnly: true,
        maxAge: 30 * 24 * 60 * 60 * 1000, // 30 days
        domain: process.env.AUTH_COOKIE_DOMAIN,
        secure: process.env.AUTH_COOKIE_DOMAIN !== 'localhost',
        signed: true,
    });

    res.status(200).send();
});
app.delete('/delete-refresh-cookie', (req, res) => {
    res.cookie(process.env.AUTH_COOKIE_NAME, '', {
        httpOnly: true,
        maxAge: 0,
        domain: process.env.AUTH_COOKIE_DOMAIN,
        secure: process.env.AUTH_COOKIE_DOMAIN !== 'localhost',
        signed: true,
    });

    res.status(200).send();
});

app.listen(4000, () => console.log('Proxy running on port 4000'));
