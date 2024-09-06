import express from "express";
import bodyParser from "body-parser";
import http from "http";
import env from "dotenv";
// import cors from "cors";
import routerUser from "./module/User/Router/api.ts";
import routerCompany from "./module/Company/Router/api.ts";
// import { startSession } from "./module/Whatsapp/index.ts";
const apps = express();
const httpServer = http.createServer(apps);
env.config();
const port = process.env.PORT ?? 8000;

apps.use(bodyParser.urlencoded({ extended: false }));
apps.use(bodyParser.json());
apps.use(function (req, res, next) {
  res.header("Access-Control-Allow-Origin", "*");
  res.header("Access-Control-Allow-Methods", "*");
  res.header(
    "Access-Control-Allow-Headers",
    "Origin, X-Requested-With, Content-Type, Accept, Authorization"
  );
  if ("OPTIONS" == req.method) res.sendStatus(200);
  else next();
});
// apps.use(cors());
apps.use('/user', routerUser);
apps.use('/company', routerCompany);

const callback = async () => {
  // await startSession();
  console.log(`Server started on http://localhost:${port}`);
};
httpServer.listen(port, async () => callback());
