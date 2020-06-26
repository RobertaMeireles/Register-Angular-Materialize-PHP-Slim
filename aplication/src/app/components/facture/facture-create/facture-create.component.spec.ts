import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FactureCreateComponent } from './facture-create.component';

describe('FactureCreateComponent', () => {
  let component: FactureCreateComponent;
  let fixture: ComponentFixture<FactureCreateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FactureCreateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FactureCreateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
