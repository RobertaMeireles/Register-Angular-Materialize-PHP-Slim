import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FactureCrudComponent } from './facture-crud.component';

describe('FactureCrudComponent', () => {
  let component: FactureCrudComponent;
  let fixture: ComponentFixture<FactureCrudComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FactureCrudComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FactureCrudComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
