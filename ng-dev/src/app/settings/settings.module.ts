import { NgModule } from '@angular/core';
import { NgxDoBootstrapModule } from '@wanoo21/ngx-lazy-modules';

import { SharedModule } from '../shared/shared.module';
import { SettingsComponent } from './settings.component';

@NgModule({
  declarations: [SettingsComponent],
  imports: [
    SharedModule
  ]
})
export class SettingsModule extends NgxDoBootstrapModule {
  bootstrapComponent = SettingsComponent
}
