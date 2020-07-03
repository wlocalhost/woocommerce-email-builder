import { NgModule } from '@angular/core';
import { SharedModule } from '../shared/shared.module';

import { TemplatesComponent } from './templates.component';
import { NgDoBootstrapModule } from "../NgDoBoostrapModule";

@NgModule({
  declarations: [TemplatesComponent],
  imports: [
    SharedModule
  ]
})
export class TemplatesModule extends NgDoBootstrapModule {
  bootstrapComponent = TemplatesComponent
}
