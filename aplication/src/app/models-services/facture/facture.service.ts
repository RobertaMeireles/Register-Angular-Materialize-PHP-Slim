import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Invoices } from './../../models-project/Invoices';
import { Observable } from 'rxjs';


@Injectable({
  providedIn: 'root'
})
export class FactureService {

  //ATRIBUTO
  baseUrl = 'http://localhost:8080/api/invoices'

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

  //METODO PARA SALVAR OS ITENS NA BASE DE DADOS
  //Vai retornar um observable do tipo produto
  create(invoices:Invoices):Observable<Invoices>{
      return this.http.post<Invoices>(this.baseUrl,invoices)
  }

}

