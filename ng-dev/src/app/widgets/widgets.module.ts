import { NgModule } from '@angular/core';
import { NgDoBootstrapModule } from '@wanoo21/ngx-lazy-modules';

import { WidgetsComponent } from './widgets.component';
import { SharedModule } from '../shared/shared.module';


@NgModule({
  declarations: [WidgetsComponent],
  imports: [
    SharedModule
  ]
})
export class WidgetsModule extends NgDoBootstrapModule {
  bootstrapComponent = WidgetsComponent
}
