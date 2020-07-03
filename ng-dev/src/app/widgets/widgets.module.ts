import { NgModule } from '@angular/core';

import { WidgetsComponent } from './widgets.component';
import { SharedModule } from '../shared/shared.module';
import { NgDoBootstrapModule } from '../NgDoBoostrapModule';


@NgModule({
  declarations: [WidgetsComponent],
  imports: [
    SharedModule
  ]
})
export class WidgetsModule extends NgDoBootstrapModule {
  bootstrapComponent = WidgetsComponent
}
