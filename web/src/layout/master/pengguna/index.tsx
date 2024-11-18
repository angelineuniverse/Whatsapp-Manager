import { Component, ReactNode, Suspense } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";
import { Button, Dialog, Table } from "@angelineuniverse/design";
import { tables, remove } from "./controller";
import clsx from "clsx";

class Pengguna extends Component<RouterInterface> {
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
      popDelete: false,
      loading: false,
      detail: undefined,
    };
    this.callTable = this.callTable.bind(this);
  }

  componentDidMount(): void {
    this.callTable();
  }
  callTable(page?: object) {
    tables(page).then((res) => {
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
        <Suspense>
          <Table
            useCreate
            useHeadline
            title="Atur semua Pengguna"
            createTitle="Tambah Pengguna"
            create={() => {
              this.props.navigate("add");
            }}
            changePage={(page: number) => {
              this.callTable({ page: page });
            }}
            onSort={(type, key) => {
              this.callTable({
                type: type,
                key: key,
              });
            }}
            column={this.state.index.column}
            data={this.state.index.data}
            property={this.state.index.property}
            custom={(row: any, key: string) => (
              <div>
                {key === "avatar" && (
                  <img
                    className=" rounded-full border border-gray-300 mx-auto h-9 w-9"
                    src={row.link}
                    alt="avatar"
                  />
                )}
                {key === "role" && (
                  <p
                    className={clsx(
                      `bg-${row.user_role?.role?.color}-100 px-2 py-1 rounded-lg text-xs text-${row.user_role?.role?.color}-700`,
                      "w-fit font-intermedium mx-auto"
                    )}
                  >
                    {row.user_role?.role?.title ?? "-"}
                  </p>
                )}
              </div>
            )}
            onEvent={(event, key) => {
              switch (key) {
                case "delete":
                  this.setState({
                    detail: event,
                    popDelete: true,
                  });
                  break;
                default:
                  this.props.navigate("show/" + event.id);
                  break;
              }
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

export default withRouterInterface(Pengguna);
