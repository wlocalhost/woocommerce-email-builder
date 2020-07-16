import { NgModule } from '@angular/core';
import { NgxDoBootstrapModule } from '@wanoo21/ngx-lazy-modules';

import { WidgetsComponent } from './widgets.component';
import { SharedModule } from '../shared/shared.module';


@NgModule({
  declarations: [WidgetsComponent],
  imports: [
    SharedModule
  ]
})
export class WidgetsModule extends NgxDoBootstrapModule {
  bootstrapComponent = WidgetsComponent
}
