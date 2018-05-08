import { Component, ElementRef } from '@angular/core';
import { NgxPermissionsService } from 'ngx-permissions';

@Component({
  selector: 'app-sidebar',
  templateUrl: './app-sidebar.component.html'
})
export class AppSidebar {

  constructor(private permissionsService: NgxPermissionsService, private el: ElementRef) { }

  //wait for the component to render completely
  ngOnInit(): void {
    
    const perm = [localStorage.getItem('tecprecinc_rol')];
    this.permissionsService.flushPermissions();
    this.permissionsService.loadPermissions(perm);
    
    var nativeElement: HTMLElement = this.el.nativeElement,
    parentElement: HTMLElement = nativeElement.parentElement;
    // move all children out of the element
    while (nativeElement.firstChild) {
      parentElement.insertBefore(nativeElement.firstChild, nativeElement);
    }
    // remove the empty element(the host)
    parentElement.removeChild(nativeElement);
  }
}
