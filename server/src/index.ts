import express from "express";
import bodyParser from "body-parser";
import http from "http";
import env from "dotenv";
import routerUser from "./module/User/Router/api.ts";
import routerCompany from "./module/Company/Router/api.ts";
// import { startSession } from "./module/Whatsapp/index.ts";
const apps = express();
const httpServer = http.createServer(apps);
env.config();
const port = process.env.PORT ?? 8000;

apps.use(bodyParser.urlencoded({ extended: false }));
apps.use(bodyParser.json());
apps.use('/user', routerUser);
apps.use('/company', routerCompany);

const callback = async () => {
  // await startSession();
  console.log(`Server started on http://localhost:${port}`);
};
httpServer.listen(port, async () => callback());
