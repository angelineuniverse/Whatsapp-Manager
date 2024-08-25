import { Component } from "react";
import { Outlet } from "react-router-dom";

class Dashboard extends Component {
  render() {
    return (
      <div>
        <p>Ini Dashboard Awal</p>
        <Outlet></Outlet>
      </div>
    );
  }
}

export default Dashboard;
