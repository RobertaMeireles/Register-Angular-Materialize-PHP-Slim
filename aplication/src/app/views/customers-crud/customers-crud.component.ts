import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-customers-crud',
  templateUrl: './customers-crud.component.html',
  styleUrls: ['./customers-crud.component.css']
})
export class CustomersCrudComponent implements OnInit {

  //necessario devido a necessidade de mudar de rota ao clicar no botão
  constructor(private router:Router) { }

  ngOnInit(): void {
  }

  navigateToCustomersCreate(){
    this.router.navigate(['/customer/create'])
  }
 
}

