import { Component, OnInit, Input } from '@angular/core';
import {CommonModule, NgClass} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { RutaService } from '../../../services/ruta.service';
import { trazabilidadComponent } from './trazabilidad.component';

@Component({
  selector: 'app-infotra',
  templateUrl: 'infotra.component.html'
})
export class infotraComponent {
  public prov: any;
  public pedidos: any;
  public productos: any;
  public proveedor: any='';

  @Input() informacion:any;
  public categorias:any;
  public tipos:any;
  public rubros:any;
  public departamentos:any;
  public permisos_departs:any=[];
  public isCrear=false;
  public trazas:any;
  public emisor:any={
    nombre:''
  };
  constructor(private http: HttpClient, private ruta: RutaService, private parent: trazabilidadComponent) {

  }

   ngOnInit(): void {
      console.log(this.informacion);
      //http://localhost/proyTecprecincCompras/tecprecincComprasAPI/public/trazas/1
      this.http.get(this.ruta.get_ruta()+'trazas/'+this.informacion.id)
           .toPromise()
           .then(
           data => {
             this.trazas=data;
              this.trazas=this.trazas.Trazas;
              console.log(this.trazas);
              for (var i = 0; i < this.trazas.length; i++) {
                if(this.trazas[i].departamento_emisor==null) {
                  this.trazas[i].departamento_emisor=this.emisor;
                }
                if(this.trazas[i].usuario_emisor==null) {
                  this.trazas[i].usuario_emisor=this.emisor;
                }
                
              }
              this.productList = this.trazas;
              this.filteredItems = this.productList;
              this.init();
             // this.loading=false;
            },
           msg => { 
             console.log(msg);
             //this.loading=false;
           });
      if(this.informacion.id==0) {
        this.isCrear=true;
      }else{
        this.isCrear=false;
      }
      this.categorias=this.parent.getCategorias();
      this.tipos=this.parent.getTipos();
      this.rubros=this.parent.getRubros();
      this.departamentos=this.parent.getDepartamentos();
      if(this.informacion!=undefined) {
       
      }
      for (var i = 0; i < this.departamentos.length; i++) {
        this.departamentos[i].bandera=false;
        for (var j = 0; j < this.informacion.departamentos.length; j++) {
          if(this.informacion.departamentos[j].id==this.departamentos[i].id) {
            this.departamentos[i].bandera=true;
            this.permisos_departs.push({"id":this.informacion.departamentos[j].id});
          }
        }
      }
    }

    volver(){
      this.parent.atras();
    }
    checkAdd(id){
     console.log(id);
     if(this.permisos_departs.length>0) {
       var b=0;
       for (var i = 0; i < this.permisos_departs.length; i++) {
         if(this.permisos_departs[i].id==id) {
           b=1;
           this.permisos_departs.splice(i, 1);
           this.delDepart(id);
         }
       }
       if(b==0) {
         this.addDepart(id);
       }
     }else if(this.permisos_departs.length==0){
       this.addDepart(id);
     }
     
     console.log(this.permisos_departs);
     console.log(this.informacion);
    }

    addDepart(id){
      this.permisos_departs.push({"id":id});
      this.informacion.departamentos.push({"id":id});
    }
    delDepart(id){
      for (var i = 0; i < this.informacion.departamentos.length; i++) {
        if(this.informacion.departamentos[i].id==id) {
          this.informacion.departamentos.splice(i, 1);
        }
          
      }
    }

    editar(){
      this.informacion.permisos_departs=JSON.stringify(this.permisos_departs);
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

     //-------------------------------------------------------------------------------------------------------------------------
   
   filteredItems : any;
   productList : any;
   pages : number = 4;
   pageSize : number = 10;
   pageNumber : number = 0;
   currentIndex : number = 1;
   items: any;
   pagesIndex : Array<number>;
   pageStart : number = 1;
   inputName : string = '';

   init(){
         this.currentIndex = 1;
         this.pageStart = 1;
         this.pages = 4;

         this.pageNumber = parseInt(""+ (this.filteredItems.length / this.pageSize));
         if(this.filteredItems.length % this.pageSize != 0){
            this.pageNumber ++;
         }
    
         if(this.pageNumber  < this.pages){
               this.pages =  this.pageNumber;
         }
       
         this.refreshItems();
         console.log("this.pageNumber :  "+this.pageNumber);
   }
   FilterByName(){
      this.filteredItems = [];
      if(this.inputName != ""){
            for (var i = 0; i < this.productList.length; i++) {
              if (this.productList[i].operacion_id==this.inputName) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].stock.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].departamento_emisor.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].usuario_emisor.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].departamento_receptor.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].usuario_receptor.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].tipo.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }
            }
      }else{
         this.filteredItems = this.productList;
      }
      console.log(this.filteredItems);
      this.init();
   }
   fillArray(): any{
      var obj = new Array();
      for(var index = this.pageStart; index< this.pageStart + this.pages; index ++) {
                  obj.push(index);
      }
      return obj;
   }
 refreshItems(){
               this.items = this.filteredItems.slice((this.currentIndex - 1)*this.pageSize, (this.currentIndex) * this.pageSize);
               this.pagesIndex =  this.fillArray();
   }
   prevPage(){
      if(this.currentIndex>1){
         this.currentIndex --;
      } 
      if(this.currentIndex < this.pageStart){
         this.pageStart = this.currentIndex;
      }
      this.refreshItems();
   }
   nextPage(){
      if(this.currentIndex < this.pageNumber){
            this.currentIndex ++;
      }
      if(this.currentIndex >= (this.pageStart + this.pages)){
         this.pageStart = this.currentIndex - this.pages + 1;
      }
 
      this.refreshItems();
   }
    setPage(index : number){
         this.currentIndex = index;
         this.refreshItems();
    }
}
