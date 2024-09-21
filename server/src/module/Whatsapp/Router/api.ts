import express from 'express';
import { started } from '../Controller/WaController.ts';
const routerWa = express.Router();

routerWa.get('/start', started); // wa -> GET index
// routerWa.get('/show', show); // wa -> GET show
// routerWa.post('/', store); // wa -> POST store
// routerWa.post('/login', login); // wa -> POST store
// routerWa.delete('/:id', destroy); // wa -> POST store

export default routerWa;