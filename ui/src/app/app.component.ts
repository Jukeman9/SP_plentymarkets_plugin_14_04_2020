import {Component, OnInit} from '@angular/core';
import {NgForm} from "@angular/forms";
import {BackendService} from "./services/backend.service";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})

export class AppComponent implements OnInit {
  orderStatuses: Status[] = [];
  spStatuses: Status[] = [];
  mapping: object;

  constructor(private backend: BackendService) {
  }

  onSubmit(f: NgForm) {
    console.log(f.value);
    this.backend.postMapping(f.value)
      .subscribe((res) => {
        console.log(res);
      }, (error) => {
        console.error(error);
      });
  }

  ngOnInit(): void {
    this.backend.getData().subscribe((res) => {
      console.log(res);
      this.orderStatuses = res["orderStatuses"] as Status[];
      this.spStatuses = res["spStatuses"] as Status[];
      this.mapping = res["mapping"] as [];
    }, (error) => {
      console.error(error);
    });
  }
}

class Status {
  public id: number;
  public name: string;
}
