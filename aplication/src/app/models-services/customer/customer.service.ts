import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Customer } from '../../models-project/Customer';
import { Observable } from 'rxjs';


@Injectable({
  providedIn: 'root'
})
export class CustomerService {

  baseURL = 'http://localhost:8080/api/products'

  constructor(private snackBar: MatSnackBar,
              private http: HttpClient) { }


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
  create(customer:Customer):Observable<Customer>{
    return this.http.post<Customer>(this.baseURL,customer)
  }

}
