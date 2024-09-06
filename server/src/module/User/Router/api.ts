import express from 'express';
import { store, index, login, show, destroy } from '../Controller/UserController.ts';
const routerUser = express.Router();

routerUser.get('/', index); // user -> GET index
routerUser.get('/show', show); // user -> GET show
routerUser.post('/', store); // user -> POST store
routerUser.post('/login', login); // user -> POST store
routerUser.delete('/:id', destroy); // user -> POST store

export default routerUser;