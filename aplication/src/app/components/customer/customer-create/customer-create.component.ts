import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Customer } from './../../../models-project/Customer';
import { CustomerService } from 'src/app/models-services/customer/customer.service';

@Component({
  selector: 'app-customer-create',
  templateUrl: './customer-create.component.html',
  styleUrls: ['./customer-create.component.css']
})
export class CustomerCreateComponent implements OnInit {

  //ATRIBUTOS
  customer: Customer={
    nome:'',
    idade:null,
    morada:'',
    cod_postal:null,
  }

  constructor(private router:Router, 
              private customerService: CustomerService ) { }

  ngOnInit(): void {
  }

  //METODO PARA ENVIAR DADOS PARA O SERVIÇO
  createCustomer(){
    //enviar o objeto para o serviço para que o serviço envie para a base de dados
    this.customerService.create(this.customer).subscribe(()=>{
    this.customerService.showMessage('Successfully!')//Passar a seguinte mensagem para o serviço
    this.router.navigate(['/customers'])//mudar em seguida a rota do usuário
    })
      
  }

  //CANCELAR O CADASTRO DO CUSTOMER
  cancel() :void{
    this.router.navigate(['/customers']);

  }
}
