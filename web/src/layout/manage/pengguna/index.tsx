import { Component, ReactNode, Suspense } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";
import { Table } from "@angelineuniverse/design";
import { tables } from "./controller";

class Pengguna extends Component<RouterInterface> {
  state: Readonly<{
    index: any;
  }>;
  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      index: {
        column: [],
        data: undefined,
      },
    };
    this.callTable = this.callTable.bind(this);
  }

  componentDidMount(): void {
    this.callTable();
  }
  callTable() {
    tables().then((res) => {
      this.setState({
        index: {
          column: res.data.column,
          data: res.data.data,
        },
      });
    });
  }

  render(): ReactNode {
    return (
      <div className="">
        <p>{this.state.index?.data?.name}</p>
        <Suspense>
          <Table
            useCreate
            useHeadline
            title="Atur semua Pengguna"
            createTitle="Tambah Pengguna"
            create={() => {
              this.props.navigate("add");
            }}
            column={this.state.index.column}
            data={this.state.index.data}
          />
        </Suspense>
      </div>
    );
  }
}

export default withRouterInterface(Pengguna);
