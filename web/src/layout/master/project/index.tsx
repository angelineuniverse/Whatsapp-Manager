import { Component, ReactNode, Suspense } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../router/interface";
import { Button, Dialog, Table } from "@angelineuniverse/design";
import { remove, tables, activated } from "./controller";
import clsx from "clsx";

class Index extends Component<RouterInterface> {
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
  async activasi(row: any, status: number) {
    await activated(row.id, { m_status_tabs_id: status }).then((res) => {
      this.callTable();
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
            title="Kelola semua Project"
            createTitle="Tambah Project"
            property={this.state.index.property}
            onEvent={(event, key) => {
              switch (key) {
                case "activated":
                  this.activasi(event, 1);
                  break;
                case "not_activated":
                  this.activasi(event, 2);
                  break;
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
            create={() => {
              this.props.navigate("add");
            }}
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
                  {row.link && <img width={80} src={row.link} alt="avatar" />}
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

export default withRouterInterface(Index);
