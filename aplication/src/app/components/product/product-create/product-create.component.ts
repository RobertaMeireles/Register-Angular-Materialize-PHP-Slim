import { Component, OnInit } from '@angular/core';
import { ProductService } from './../../../models-services/product/product.service';
import { Router } from '@angular/router';
import { Product } from './../../../models-project/Product';

@Component({
  selector: 'app-product-create',
  templateUrl: './product-create.component.html',
  styleUrls: ['./product-create.component.css']
})
export class ProductCreateComponent implements OnInit {

  //ATRIBUTO
  product: Product = {
    designacao:'',
    descricao:'',
    preco:null,
    id_categoria:null
  }

  constructor(private productService: ProductService, 
              private router:Router) { }

  ngOnInit(): void {
   
  }

  //METODO PARA CRIAR PRODUTO
  createProduct(): void{
    //enviar o objeto para o serviço para que o serviço envie para a base de dados
    this.productService.create(this.product).subscribe(()=>{
      this.productService.showMessage('Successfully!')//Passar a seguinte mensagem para o serviço
      this.router.navigate(['/products']);//mudar em seguida a rota do usuário
    })
  }

  //METODO PARA SAIR DA PAGINA CRIAR PRODUTO
  cancel(): void{
    this.router.navigate(['/products']);
  }

}
