import { Component, ReactNode, Suspense } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";
import { Tab, TabItem } from "@angelineuniverse/design";
import { Outlet } from "react-router-dom";

class TypeUnit extends Component<RouterInterface> {
  state: Readonly<{
    selected: string | number;
  }>;
  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      selected: 1,
    };
  }

  render(): ReactNode {
    return (
      <div className="">
        <Suspense>
          <Tab tabDirection="horizontal" valueSelected={this.state.selected}>
            <TabItem
              label="Unit"
              value={1}
              onTabSelected={() => {
                this.props.navigate("");
              }}
            ></TabItem>
            <TabItem
              label="Type Unit"
              value={2}
              onTabSelected={() => {
                this.props.navigate("type");
              }}
            ></TabItem>
            <TabItem
              label="Status Unit"
              value={3}
              onTabSelected={() => {
                this.props.navigate("status");
              }}
            ></TabItem>
          </Tab>
        </Suspense>
        <div className=" overflow-hidden">
          <Outlet></Outlet>
        </div>
      </div>
    );
  }
}

export default withRouterInterface(TypeUnit);
