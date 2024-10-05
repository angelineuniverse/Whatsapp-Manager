import { Component, ReactNode } from "react";
import { Outlet } from "react-router-dom";

class Base extends Component {
  render(): ReactNode {
    return (
      <div>
        <Outlet></Outlet>
      </div>
    );
  }
}

export default Base;
