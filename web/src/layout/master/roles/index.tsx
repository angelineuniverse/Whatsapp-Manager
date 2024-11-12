import { Component, ReactNode, Suspense } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";
import { Button, Dialog, Table } from "@angelineuniverse/design";
import { remove, tables } from "./controller";
import clsx from "clsx";

class Akses extends Component<RouterInterface> {
  state: Readonly<{
    index: any;
    detail: any;
    popDelete: boolean;
    loading: boolean;
  }>;
  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      index: {
        column: [],
        data: undefined,
        property: undefined,
      },
      detail: undefined,
      popDelete: false,
      loading: false,
    };
    this.callTable = this.callTable.bind(this);
  }

  componentDidMount(): void {
    this.callTable();
  }
  async callTable() {
    await tables().then((res) => {
      this.setState({
        index: {
          column: res.data.column,
          data: res.data.data,
          property: res.data.property,
        },
      });
    });
  }

  async deleted() {
    this.setState({
      loading: true,
    });
    await remove(this.state.detail?.id)
      .then((res) => {
        this.callTable();
        this.setState({
          popDelete: false,
          loading: false,
        });
      })
      .catch(() => {
        this.setState({
          loading: false,
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
            title="Atur semua Roles"
            createTitle="Tambah Roles"
            create={() => {
              this.props.navigate("add");
            }}
            property={this.state.index.property}
            delete={(event) => {
              this.setState({
                detail: event,
                popDelete: true,
              });
            }}
            show={(event) => this.props.navigate("show/" + event.id)}
            column={this.state.index.column}
            data={this.state.index.data}
            custom={(row: any) => {
              return (
                <div>
                  {row.color && (
                    <div
                      className={clsx(
                        " h-4 w-4 rounded-md mx-auto ",
                        `bg-${row.color}-500`
                      )}
                    ></div>
                  )}
                </div>
              );
            }}
          />
          <Dialog
            onOpen={this.state.popDelete}
            title="Hapus Data"
            size="small"
            classHeading="uppercase text-red-500"
            useHeading
            onClose={() => {
              this.setState({
                popDelete: false,
              });
            }}
          >
            <p className=" text-xs font-interregular">
              Saat anda menghapus item yang dipilih, semua informasi yang
              terdapat pada system akan dihapus seluruhnya. Ingin melanjutkan ?
            </p>
            <div className="flex justify-end gap-x-5 mt-6">
              <Button
                title="Kembali"
                theme="transparent"
                size="small"
                onClick={() => {
                  this.setState({
                    popDelete: false,
                  });
                }}
                width="block"
              />
              <Button
                title="Hapus Data"
                theme="error"
                size="small"
                width="block"
                isLoading={this.state.loading}
                onClick={() => this.deleted()}
              />
            </div>
          </Dialog>
        </Suspense>
      </div>
    );
  }
}

export default withRouterInterface(Akses);
