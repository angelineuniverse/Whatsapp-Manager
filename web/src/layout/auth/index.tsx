import { Component } from "react";
import Input from "../../component/input/input";
import Button from "../../component/button/button";
import { login } from "./controller";
import { setCookie } from "typescript-cookie";
import { RouterInterface, withRouterInterface } from "../../router/interface";
class Auth extends Component<RouterInterface> {
  state: Readonly<{
    email: string | undefined;
    password: string | undefined;
    loading: boolean;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = { email: undefined, password: undefined, loading: false };
    this.auth = this.auth.bind(this);
  }

  async auth() {
    this.setState({ loading: true });
    await login({ ...this.state })
      .then((res) => {
        this.setState({ loading: false });
        setCookie("LOG", res.data?.token);
        return this.props.navigate("/");
      })
      .catch((err) => {
        console.log(err); //fndjfnsjdfnj
      });
  }
  render() {
    return (
      <div className="h-screen max-h-screen flex justify-center items-center bg-gray-50">
        <div className="rounded-md shadow-xl border border-gray-300 bg-white p-5 w-3/12">
          <h1 className="uppercase font-interbold text-sm">Whatsapp Manager</h1>
          <h6 className="mt-6 font-interbold text-3xl mb-1">Masuk</h6>
          <p className="text-xs font-interregular">
            Masukan informasi email dan password anda
          </p>
          <div className="mt-5">
            <Input
              label="Email"
              type="text"
              size="medium"
              onValueChange={(value: string) => this.setState({ email: value })}
            />
            <Input
              label="Password"
              type="password"
              className="mt-2 mb-8"
              size="medium"
              onValueChange={(value: string) =>
                this.setState({ password: value })
              }
            />
            <Button
              title="Masuk"
              theme="primary"
              size="medium"
              width="full"
              isLoading={this.state.loading}
              onClick={this.auth}
            />
          </div>
        </div>
      </div>
    );
  }
}

export default withRouterInterface(Auth);
