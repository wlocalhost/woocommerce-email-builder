import { NgModule } from '@angular/core';
import { NgDoBootstrapModule } from '@wanoo21/ngx-lazy-modules';

import { SharedModule } from '../shared/shared.module';
import { TemplatesComponent } from './templates.component';

@NgModule({
  declarations: [TemplatesComponent],
  imports: [
    SharedModule
  ]
})
export class TemplatesModule extends NgDoBootstrapModule {
  bootstrapComponent = TemplatesComponent
}
