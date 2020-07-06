import { NgModule } from '@angular/core';
import { NgDoBootstrapModule } from '@wanoo21/ngx-lazy-modules';

import { WoocommerceTemplatesComponent } from './woocommerce-templates.component';
import { SharedModule } from '../shared/shared.module';



@NgModule({
  declarations: [WoocommerceTemplatesComponent],
  imports: [
    SharedModule
  ]
})
export class WoocommerceTemplatesModule extends NgDoBootstrapModule {
  bootstrapComponent = WoocommerceTemplatesComponent
}
