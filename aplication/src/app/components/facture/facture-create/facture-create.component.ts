import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Invoices } from './../../../models-project/Invoices';
import { FactureService } from './../../../models-services/facture/facture.service';

@Component({
  selector: 'app-facture-create',
  templateUrl: './facture-create.component.html',
  styleUrls: ['./facture-create.component.css']
})
export class FactureCreateComponent implements OnInit {

  //ATRIBUTO
  invoices: Invoices = {
    id_cliente: null,
    products:null
  }

  constructor(private invoiceService: FactureService, 
              private router:Router) { }

  ngOnInit(): void {
   
  }

  //METODO PARA CRIAR PRODUTO
  createProduct(): void{
    //enviar o objeto para o serviço para que o serviço envie para a base de dados
      this.invoiceService.create(this.invoices).subscribe(()=>{
      this.invoiceService.showMessage('Successfully!')//Passar a seguinte mensagem para o serviço
      this.router.navigate(['/products']);//mudar em seguida a rota do usuário
    })
  }

  //METODO PARA SAIR DA PAGINA CRIAR PRODUTO
  cancel(): void{
    this.router.navigate(['/products']);
  }

}
