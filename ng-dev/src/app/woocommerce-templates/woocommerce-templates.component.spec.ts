import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WoocommerceTemplatesComponent } from './woocommerce-templates.component';

describe('WoocommerceTemplatesComponent', () => {
  let component: WoocommerceTemplatesComponent;
  let fixture: ComponentFixture<WoocommerceTemplatesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WoocommerceTemplatesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WoocommerceTemplatesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
