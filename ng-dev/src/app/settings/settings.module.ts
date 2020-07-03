import { NgModule } from '@angular/core';

import { SharedModule } from '../shared/shared.module';
import { SettingsComponent } from './settings.component';
import { NgDoBootstrapModule } from '../NgDoBoostrapModule';



@NgModule({
  declarations: [SettingsComponent],
  imports: [
    SharedModule
  ]
})
export class SettingsModule extends NgDoBootstrapModule {
  bootstrapComponent = SettingsComponent
}
