import { enableProdMode, Type } from '@angular/core';
import { APP_BASE_HREF } from '@angular/common';
import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';

// import { AppModule } from './app/app.module';
import { environment } from './environments/environment';

if (environment.production) {
  enableProdMode();
}

(async function () {
  const { current_slug } = globalThis.NGB
  let module: Type<any>

  if (current_slug === 'ngb-templates') {
    module = await import('./app/templates/templates.module').then(m => m.TemplatesModule)
  } else if (current_slug === 'ngb-woocommerce') {
    module = await import('./app/woocommerce-templates/woocommerce-templates.module').then(m => m.WoocommerceTemplatesModule)
  } else if (current_slug === 'ngb-widgets') {
    module = await import('./app/widgets/widgets.module').then(m => m.WidgetsModule)
  } else if (current_slug === 'ngb-settings') {
    module = await import('./app/settings/settings.module').then(m => m.SettingsModule)
  } else {
    module = await import('./app/app.module').then(m => m.AppModule)
  }

  const moduleRef = await platformBrowserDynamic([
    {
      provide: APP_BASE_HREF,
      useValue: globalThis.NGB.app_base_href
    }
  ]).bootstrapModule(module)

  console.log(moduleRef)
}()).catch(err => console.error(err));

