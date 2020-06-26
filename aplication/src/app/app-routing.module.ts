import { NgModule, Component } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { HomeComponent } from './views/home/home.component';
import { ProductCrudComponent } from './views/product-crud/product-crud.component';
import { ProductCreateComponent } from './components/product/product-create/product-create.component';
import { CustomersCrudComponent } from './views/customers-crud/customers-crud.component';


import { CustomerCreateComponent } from './components/customer/customer-create/customer-create.component';
import { FactureCrudComponent } from './views/facture-crud/facture-crud.component';
import { FactureCreateComponent } from './components/facture/facture-create/facture-create.component';



//colocar as rotas
const routes: Routes = [
  //ROTA HOME
  {
  //Rota home
    path: "",
    component: HomeComponent
  },
  //ROTA PRODUTOS
  {
  //rota produtos CRUD
    path:"products",
    component: ProductCrudComponent
  },
  {
  //rota para formulário para criar novo product
    path:"product/create",
    component: ProductCreateComponent
  },
  //ROTA CUSTOMERS
  {
  //rota customers CRUD
    path:"customers",
    component: CustomersCrudComponent
  },
  {
  //rota para formulário para criar novo customer
    path:"customer/create",
    component: CustomerCreateComponent
  },
  //ROTA PARA FATURAS
  {
  //rota para faturas
    path:"invoices",
    component: FactureCrudComponent
  },
  {
  //rota para formulário para criar nova facture
    path:"invoices/create",
    component: FactureCreateComponent
    }

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
exports: [RouterModule]
})
export class AppRoutingModule { }
