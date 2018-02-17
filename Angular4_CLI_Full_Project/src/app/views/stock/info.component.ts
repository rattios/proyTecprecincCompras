import { Component, OnInit, Input } from '@angular/core';
import {CommonModule, NgClass} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { RutaService } from '../../services/ruta.service';
import { stockComponent } from './stock.component';

@Component({
  selector: 'app-info',
  templateUrl: 'info.component.html'
})
export class infoComponent {
  public prov: any;
  public pedidos: any;
  public productos: any;
  public proveedor: any='';

  @Input() informacion:any;
  public categorias:any;
  public tipos:any;
  public rubros:any;

  constructor(private http: HttpClient, private ruta: RutaService, private parent: stockComponent) {

  }

   ngOnInit(): void {
      console.log(this.informacion);
      this.categorias=this.parent.getCategorias();
      this.tipos=this.parent.getTipos();
      this.rubros=this.parent.getRubros();

      if(this.informacion!=undefined) {
       
      }
    }

    volver(){
      this.parent.atras();
    }

    editar(){
      console.log(this.informacion);
      this.http.put(this.ruta.get_ruta()+'stock/'+this.informacion.id,this.informacion)
       .toPromise()
       .then(
       data => {
          console.log(data);

          alert('exito');
        },
       msg => { 
         console.log(msg);
         alert(JSON.stringify(msg));
       });
    }
}
