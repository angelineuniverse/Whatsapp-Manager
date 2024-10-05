import express from 'express';
import { index } from '../Controller/MenuAccessController.ts';
const routerMenuAccess = express.Router();

routerMenuAccess.get('/', index); // user -> GET index
// routerMenuAccess.get('/show', show); // user -> GET show
// routerMenuAccess.post('/', store); // user -> POST store
// routerMenuAccess.post('/login', login); // user -> POST store
// routerMenuAccess.delete('/:id', destroy); // user -> POST store
export default routerMenuAccess;