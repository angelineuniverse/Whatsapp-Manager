import express from 'express';
import { store, index,login } from '../Controller/UserController.ts';
const routerUser = express.Router();

routerUser.get('/', index); // user -> GET index
routerUser.post('/', store); // user -> POST store
routerUser.post('/login', login); // user -> POST store

export default routerUser;