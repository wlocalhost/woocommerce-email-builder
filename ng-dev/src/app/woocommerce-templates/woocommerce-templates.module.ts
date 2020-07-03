import { NgModule } from '@angular/core';
import { WoocommerceTemplatesComponent } from './woocommerce-templates.component';
import { SharedModule } from '../shared/shared.module';
import { NgDoBootstrapModule } from '../NgDoBoostrapModule';



@NgModule({
  declarations: [WoocommerceTemplatesComponent],
  imports: [
    SharedModule
  ]
})
export class WoocommerceTemplatesModule extends NgDoBootstrapModule {
  bootstrapComponent = WoocommerceTemplatesComponent
}
