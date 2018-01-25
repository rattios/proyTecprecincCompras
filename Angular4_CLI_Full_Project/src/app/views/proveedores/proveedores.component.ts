import { Component } from '@angular/core';
import {CommonModule} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';

@Component({
  templateUrl: 'proveedores.component.html'
})
export class ProveedoresComponent {

  public proveedores: any;
  public productos: any;
  public proveedor: any='';
  constructor(private http: HttpClient) {

  }

   ngOnInit(): void {

      this.http.get('http://vivomedia.com.ar/Tecprecinc/proveedores.json')
           .toPromise()
           .then(
           data => {
           	  this.proveedores=data;
              console.log(this.proveedores);
              
            },
           msg => { 
             console.log(msg);
           });
       this.http.get('http://vivomedia.com.ar/Tecprecinc/productos.json')
           .toPromise()
           .then(
           data => {
           	  this.productos=data;
              console.log(this.productos);
              
            },
           msg => { 
             console.log(msg);
           });
    }

    ver(item){
    	this.proveedor=item.razonSocial;
    }
    setProductos(){
      console.log('asdasd');
    }
}
