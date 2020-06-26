import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-facture-crud',
  templateUrl: './facture-crud.component.html',
  styleUrls: ['./facture-crud.component.css']
})
export class FactureCrudComponent implements OnInit {

  constructor(private router: Router) { }

  ngOnInit(): void {
  }

  navigateToInvoiceCreate(){
    this.router.navigate(['/invoices/create'])
  }

}
