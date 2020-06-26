import { Directive, OnInit, Input, ViewContainerRef, TemplateRef } from '@angular/core';

//DIRETIVA APLICADA NO HOME.COMPONENTES

@Directive({
  selector: '[appFor]',
})
export class ForDirective implements OnInit {

  //EM pegar depois que vem da palavra chave em (vide a localizaçao no home.componentes)
  //Que é um array de informaçoes
  @Input('appForEm') info:number[];

  // 2 coisas (container)ViewContainerRef e (template) TemplateRef<any>:
  constructor(private container:ViewContainerRef, 
              private template:TemplateRef<any>) {}

  ngOnInit(): void{
    //associado a cada um dos itens info criando um createEmbeddedView aplicando no templete
    //assim foi criado um template para cada repetição,
    //Onde o his.template sera o li que está no home.components
    //e dentro do contexto exista um valor implicido que é o valor de cada item do array na tela
    for(let inforation of this.info){
      this.container.createEmbeddedView(this.template, {$implicit:inforation})

    }

  }

}
