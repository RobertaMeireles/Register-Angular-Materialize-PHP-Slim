import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CustomersCrudComponent } from './customers-crud.component';

describe('CustomersCrudComponent', () => {
  let component: CustomersCrudComponent;
  let fixture: ComponentFixture<CustomersCrudComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CustomersCrudComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CustomersCrudComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
