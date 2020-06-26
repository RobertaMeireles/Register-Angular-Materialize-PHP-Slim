import { Injectable } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar'; //msg avisando que foi cadastrado
import { HttpClient } from '@angular/common/http';
import { Product } from 'src/app/models-project/Product';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ProductService {

  //ATRIBUTO DA CLASSE PRODUCTSERVICE
  baseUrl = 'http://localhost:8080/api/products'

  constructor(private snackBar: MatSnackBar,
             private http:HttpClient) { }

  //METODO PARA APRESENTAR MENSAGEM AVISANDO QUE FOI FEITO O CADASTRO/MODIFICAÇÃO DO PRODUTO
  showMessage(msg: string): void{
    //usando a variavel criada no construtor e utilizando o metodo open do materalize do MatSnackBarModule
    this.snackBar.open(msg,'X',{
      duration:3000,
      horizontalPosition:"right",
      verticalPosition: "top"
    })
  }

  //METODO COM A FUNÇÃO DE INSERIR O PRODUTO NO BACKEND
  //Vai retornar um observable do tipo produto
  create(product:Product):Observable<Product>{
    return this.http.post<Product>(this.baseUrl,product)
  }
}
