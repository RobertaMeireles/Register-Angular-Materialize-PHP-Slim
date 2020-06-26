import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';


import {MatToolbarModule} from '@angular/material/toolbar'; 
import {MatSidenavModule} from '@angular/material/sidenav'; 
import {MatListModule} from'@angular/material/list'; //modulo para listar itens no header
import {MatCardModule} from'@angular/material/card'; //modulo para icones do material
import {MatButtonModule} from '@angular/material/button' //modulo do material para botoes 
import { MatSnackBarModule } from '@angular/material/snack-bar'; //msg avisando que foi cadastrado

//modulos para trabalhar com formulários
import { FormsModule } from '@angular/forms'; 
import { MatFormFieldModule } from '@angular/material/form-field'; 
import { MatInputModule } from '@angular/material/input'; 


import { HeaderComponent } from './components/template/header/header.component';
import { FooterComponent } from './components/template/footer/footer.component';
import { NavComponent } from './components/template/nav/nav.component';

import { HomeComponent } from './views/home/home.component';

import { ProductCrudComponent } from './views/product-crud/product-crud.component';
import { CustomersCrudComponent } from './views/customers-crud/customers-crud.component';
import { FactureCrudComponent } from './views/facture-crud/facture-crud.component';
import { RedDirective } from './directives/red.directive';
import { ForDirective } from './directives/for.directive';
import { ProductCreateComponent } from './components/product/product-create/product-create.component';
import { FactureCreateComponent } from './components/facture/facture-create/facture-create.component';
import { CustomerCreateComponent} from './components/customer/customer-create/customer-create.component';
import { LoginComponent } from './views/login/login.component'

import { HttpClientModule } from '@angular/common/http';




@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    FooterComponent,
    NavComponent,
    HomeComponent,
    ProductCrudComponent,
    CustomersCrudComponent,
    FactureCrudComponent,
    RedDirective,
    ForDirective,
    ProductCreateComponent,
    FactureCreateComponent,
    CustomerCreateComponent,
    LoginComponent,
    

  ],
  imports: [
  BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    MatToolbarModule,
    MatSidenavModule,
    MatListModule,
    MatCardModule,
    MatButtonModule,
    MatSnackBarModule,
    FormsModule,
    MatFormFieldModule,
    MatInputModule,
    HttpClientModule

  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
