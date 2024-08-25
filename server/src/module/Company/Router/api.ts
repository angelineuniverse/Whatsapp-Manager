import express from 'express';
import { store, index, show, destroy, update } from '../Controller/CompanyController.ts';
const routerCompany = express.Router();

routerCompany.get('/', index);
routerCompany.post('/', store);
routerCompany.get('/:id', show);
routerCompany.delete('/:id', destroy);
routerCompany.put('/:id', update);

export default routerCompany;