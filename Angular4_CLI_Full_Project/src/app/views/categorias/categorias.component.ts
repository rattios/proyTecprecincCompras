import { Component } from '@angular/core';
import {CommonModule} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';

@Component({
  templateUrl: 'categorias.component.html'
})
export class CategoriasComponent {

  public categoria: any;
  public categorias: any;
  public rubros: any;
  public tipos: any;
  public productos: any;
  public proveedor: any='';
  constructor(private http: HttpClient) {

  }

   ngOnInit(): void {

      this.http.get('http://localhost:8000/fullcategorias')
           .toPromise()
           .then(
           data => {
           	  this.categoria=data;
              console.log(this.categoria);
              this.categorias=this.categoria.categorias;
              this.rubros=this.categoria.rubros;
              this.tipos=this.categoria.tipos;
            },
           msg => { 
             console.log(msg);
           });
    }

    ver(item){
    	this.proveedor=item.razonSocial;
    }
}
