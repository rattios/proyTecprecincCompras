import { Component, OnInit, Input,Pipe, PipeTransform } from '@angular/core';
import {CommonModule, NgClass, DatePipe } from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { saveAs } from 'file-saver/FileSaver';
import { RutaService } from '../../../services/ruta.service';
import {MatDatepicker} from '@angular/material/datepicker';
import {DateAdapter} from '@angular/material';


@Component({
  templateUrl: 'trazabilidad.component.html'
})
export class trazabilidadComponent {
  public prov: any;
  public stock: any;
  public productos: any;
  public proveedor: any='';
  public loading=true;
  public verProduc=false;
  public producSelec:any;
  public trazas: any;

  public inicioD2:any=new Date();
  public finD2:any=new Date();
  public info:any={
    nombre:'',
    telefono:''
  }
   public emisor:any={
    nombre:''
  };
  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {
     this.loading=true;
      this.http.get(this.ruta.get_ruta()+'todos')
           .toPromise()
           .then(
           data => {
             this.prov=data;
           	  this.stock=this.prov.productos;

              console.log(this.stock);
              this.productList = this.stock;
              this.filteredItems = this.productList;
              this.init();
              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });

     var principio:any= new Date();
     var mes:any=principio.getMonth()+1; 
     var anio:any=principio.getYear()+1900;
     principio=mes+"-01-"+anio;
     principio=new Date(principio);
     this.inicioD2=principio;
     console.log(principio);
      var datePipe = new DatePipe("en-US");
      console.log(datePipe.transform(new Date(), 'dd-MM-yyyy'));

      this.http.get(this.ruta.get_ruta()+'trazas?inicio='+datePipe.transform(principio, 'dd-MM-yyyy')+'&fin='+datePipe.transform(new Date(), 'dd-MM-yyyy'))
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
             // this.loading=false;
            },
           msg => { 
             console.log(msg);
             //this.loading=false;
           });
    }

    
    buscar(){
      this.loading=true;
      console.log(this.inicioD2);
      console.log(this.finD2);
      var datePipe = new DatePipe("en-US");
      this.http.get(this.ruta.get_ruta()+'trazas?inicio='+datePipe.transform(this.inicioD2, 'dd-MM-yyyy')+'&fin='+datePipe.transform(this.finD2, 'dd-MM-yyyy'))
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
             this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
    }

    ver(item){
      if(item==0) {
        this.producSelec={
          categoria_id:0,
          codigo:"",
          departamentos:[],
          id:0,
          nombre:"",
          precio:0,
          rubro_id:0,
          stock:0,
          stock_min:0,
          tipo_id:0
        };
        console.log(item);
        this.verProduc=true;
      }else{
    	  this.producSelec=item;
        this.verProduc=true;
      }
    }
    public success:any;
    public fail:any;
    editar(){
      
      var send={
        nombre:this.info.nombre,
        telefono: this.info.telefono
      }
      console.log(send);
      this.http.put(this.ruta.get_ruta()+'almacen/1',send)
           .toPromise()
           .then(
           data => {
            
              console.log(data);
              this.success=true;
              setTimeout(() => {  
                this.success=false;
              }, 4000);
            },
           msg => { 
             console.log(msg);
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);

           });
    }
    atras(){
      this.verProduc=false;
    }

    public saveFile(){
      console.log('exportar');
      var blob = new Blob([document.getElementById('exportable').innerHTML], {
              type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
          });
          saveAs(blob, 'trazas.xls');
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
      if(this.inputName != ""){
            for (var i = 0; i < this.productList.length; ++i) {

              if (this.productList[i].stock==this.inputName) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].stock2==this.inputName) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].precio==this.inputName) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].codigo==this.inputName) {
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
