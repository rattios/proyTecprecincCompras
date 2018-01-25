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
  public aEditar:any= [];
  constructor(private http: HttpClient) {

  }

   ngOnInit(): void {

      this.http.get('http://localhost:8000/fullcategorias')
           .toPromise()
           .then(
           data => {
           	  this.categoria=data;
              
              this.categorias=this.categoria.categorias;
              this.rubros=this.categoria.rubros;
              this.tipos=this.categoria.tipos;

              for (var i = 0; i < this.categorias.length; ++i) {
                this.categorias[i].habilitado=true;
              }
              console.log(this.categoria);
            },
           msg => { 
             console.log(msg);
           });
    }

    editar(id){
    	console.log(id);
      for (var i = 0; i < this.categorias.length; i++) {
        if (this.categorias[i].id==id) {
          console.log(id);
          this.categorias[i].habilitado=false;
          this.aEditar.push(this.categorias[i]);
        }
      }
    }

  guardarCambios(){
    console.log(this.aEditar);
  }
}
