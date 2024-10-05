import express from 'express';
import { index } from '../Controller/MenuController.ts';
const routerMenu = express.Router();

routerMenu.get('/', index); // user -> GET index
// routerMenu.get('/show', show); // user -> GET show
// routerMenu.post('/', store); // user -> POST store
// routerMenu.post('/login', login); // user -> POST store
// routerMenu.delete('/:id', destroy); // user -> POST store
export default routerMenu;