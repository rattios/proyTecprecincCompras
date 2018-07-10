import { Component, OnInit, Input } from '@angular/core';
import {CommonModule, NgClass} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { saveAs } from 'file-saver/FileSaver';
import { RutaService } from '../../../services/ruta.service';
import { departComponent } from './depart.component';

@Component({
  selector: 'app-infodepart',
  templateUrl: 'infodepart.component.html'
})
export class infodepartComponent {
  public prov: any;
  public pedidos: any;
  public productos: any;
  public proveedor: any='';

  @Input() informacion:any;
  public categorias:any;
  public tipos:any;
  public rubros:any;
  public depart:any;
  public permisos_departs:any=[];
  public isCrear=false;
  public trazas:any;
  public general:any;
  public emisor:any={
    nombre:''
  };
  constructor(private http: HttpClient, private ruta: RutaService, private parent: departComponent) {

  }

   ngOnInit(): void {
      console.log(this.informacion);
      //http://localhost/proyTecprecincCompras/tecprecincComprasAPI/public/trazas/1
      this.http.get(this.ruta.get_ruta()+'stockDepar?departamento_id='+this.informacion.id)
           .toPromise()
           .then(
           data => {
             this.depart=data;
             this.general=data;
             console.log(data);
              this.depart=this.depart.productos;
              this.general=this.general.general;

              this.productList = this.depart;
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
      
      if(this.informacion!=undefined) {
       
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

    public saveFile(){
      console.log('exportar');
      var blob = new Blob([document.getElementById('exportable').innerHTML], {
              type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
          });
          saveAs(blob, this.informacion.nombre+'.xls');
           //FileSaver.saveAs(blob, CONFIG.ECOMMERCE_NOMBRE+".xls");
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
      this.depart = [];
      if(this.inputName != ""){
            for (var i = 0; i < this.productList.length; i++) {
              if (this.productList[i].codigo==this.inputName) {
                 this.filteredItems.push(this.productList[i]);
                 this.depart.push(this.productList[i]);
              }else if (this.productList[i].nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
                 this.depart.push(this.productList[i]);
              }
            }
      }else{
         this.filteredItems = this.productList;
         this.depart = this.productList;
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
