import { Directive, ElementRef } from '@angular/core';

//DIRETIVA APLICADA NO FOOTER.COMPONENTES

@Directive({
  selector: '[appRed]'
})
export class RedDirective {

  //injetar o el:ElementRef para alterar a cor
  constructor(private el:ElementRef) { 
    //diretiva de atributo, capaz de mexer tanto no comportamento quanto no estilo do component
    //mudando a cor para vermelho do coração
    el.nativeElement.style.color = '#e35e6b'; 
  }

}
