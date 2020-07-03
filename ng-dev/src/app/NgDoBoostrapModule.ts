import { ApplicationRef, Type } from '@angular/core';

export abstract class NgDoBootstrapModule {
    abstract bootstrapComponent: Type<any>
    rootSelectorOrNode = 'app-root'

    ngDoBootstrap(applicationRef: ApplicationRef) {
        applicationRef.bootstrap(this.bootstrapComponent, this.rootSelectorOrNode)
    }
}